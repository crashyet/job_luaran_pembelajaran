<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    // Helper to get active user (simulated)
    private function getActiveUser()
    {
        $userId = session('simulated_user_id');
        if (!$userId) {
            // Default to teacher
            $teacher = User::where('role', 'teacher')->first();
            if ($teacher) {
                session(['simulated_user_id' => $teacher->id]);
                return $teacher;
            }
        }
        return User::find($userId) ?? User::first();
    }

    // Switch simulated user
    public function simulateUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        session(['simulated_user_id' => $request->user_id]);
        return back()->with('success', 'Berhasil berganti pengguna simulator!');
    }

    // Classroom Dashboard
    public function index()
    {
        $activeUser = $this->getActiveUser();
        $allUsers = User::all();

        if ($activeUser->role === 'teacher') {
            $classes = Classroom::where('teacher_id', $activeUser->id)->get();
        } else {
            $classes = $activeUser->classroomsAsStudent;
        }

        return view('dashboard', compact('activeUser', 'allUsers', 'classes'));
    }

    // Create Class
    public function createClass(Request $request)
    {
        $activeUser = $this->getActiveUser();
        if ($activeUser->role !== 'teacher') {
            return back()->with('error', 'Hanya pengajar yang dapat membuat kelas!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
        ]);

        $code = strtoupper(Str::random(6));

        Classroom::create([
            'name' => $request->name,
            'section' => $request->section,
            'subject' => $request->subject,
            'room' => $request->room,
            'code' => $code,
            'teacher_id' => $activeUser->id,
            'banner_theme' => collect(['indigo', 'emerald', 'purple', 'rose', 'blue'])->random(),
        ]);

        return back()->with('success', 'Kelas berhasil dibuat dengan kode: ' . $code);
    }

    // Join Class
    public function joinClass(Request $request)
    {
        $activeUser = $this->getActiveUser();
        if ($activeUser->role !== 'student') {
            return back()->with('error', 'Hanya siswa yang dapat bergabung ke kelas!');
        }

        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $classroom = Classroom::where('code', strtoupper($request->code))->first();

        if (!$classroom) {
            return back()->with('error', 'Kode kelas tidak ditemukan!');
        }

        // Check if already joined
        if ($classroom->students()->where('user_id', $activeUser->id)->exists()) {
            return back()->with('error', 'Anda sudah bergabung di kelas ini!');
        }

        $classroom->students()->attach($activeUser->id);

        return back()->with('success', 'Berhasil bergabung ke kelas: ' . $classroom->name);
    }

    // Class Detail (Stream, Classwork, People, Grades)
    public function show(Classroom $classroom, Request $request)
    {
        $activeUser = $this->getActiveUser();
        $allUsers = User::all();

        // Check permission: must be class teacher or joined student
        $isTeacher = $classroom->teacher_id === $activeUser->id;
        $isStudent = $classroom->students()->where('user_id', $activeUser->id)->exists();

        if (!$isTeacher && !$isStudent) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke kelas ini!');
        }

        $tab = $request->query('tab', 'stream');

        // Fetch posts
        $posts = $classroom->posts()->with(['author', 'comments.author'])->get();

        // Separate assignments for classwork
        $assignments = $classroom->posts()->where('type', 'assignment')->get();

        // Get submissions if student
        $mySubmissions = [];
        if ($isStudent) {
            $mySubmissions = Submission::where('student_id', $activeUser->id)
                ->whereIn('post_id', $assignments->pluck('id'))
                ->get()
                ->keyBy('post_id');
        }

        // Get all submissions for grading if teacher
        $allSubmissions = [];
        if ($isTeacher) {
            $allSubmissions = Submission::whereIn('post_id', $assignments->pluck('id'))
                ->with(['student', 'assignment'])
                ->get()
                ->groupBy('post_id');
        }

        return view('classroom-detail', compact(
            'classroom',
            'activeUser',
            'allUsers',
            'tab',
            'posts',
            'assignments',
            'mySubmissions',
            'allSubmissions'
        ));
    }

    // Post Stream Announcement or Assignment
    public function storePost(Classroom $classroom, Request $request)
    {
        $activeUser = $this->getActiveUser();
        $isTeacher = $classroom->teacher_id === $activeUser->id;
        
        $request->validate([
            'content' => 'required|string',
            'type' => 'required|in:announcement,assignment',
            'title' => 'required_if:type,assignment|nullable|string|max:255',
            'points' => 'required_if:type,assignment|nullable|integer|min:0|max:100',
            'due_date' => 'required_if:type,assignment|nullable|date',
            'attachment' => 'nullable|file|max:10240', // 10MB Limit
        ]);

        if ($request->type === 'assignment' && !$isTeacher) {
            return back()->with('error', 'Hanya pengajar yang dapat membuat tugas!');
        }

        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Ensure uploads directory exists
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            
            $file->move(public_path('uploads'), $filename);
            $attachmentPath = 'uploads/' . $filename;
        }

        Post::create([
            'class_id' => $classroom->id,
            'user_id' => $activeUser->id,
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'points' => $request->points,
            'due_date' => $request->due_date,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        $msg = $request->type === 'assignment' ? 'Tugas berhasil dibuat!' : 'Pengumuman berhasil dibagikan!';
        return redirect()->route('classroom.show', [$classroom->id, 'tab' => $request->type === 'assignment' ? 'classwork' : 'stream'])->with('success', $msg);
    }

    // Comment on post
    public function storeComment(Post $post, Request $request)
    {
        $activeUser = $this->getActiveUser();
        
        $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $activeUser->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    // Student Submit Assignment
    public function submitAssignment(Post $post, Request $request)
    {
        $activeUser = $this->getActiveUser();
        if ($activeUser->role !== 'student') {
            return back()->with('error', 'Hanya siswa yang dapat mengumpulkan tugas!');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        // Check if already submitted
        $submission = Submission::where('post_id', $post->id)
            ->where('student_id', $activeUser->id)
            ->first();

        if ($submission) {
            $submission->update([
                'content' => $request->content,
                'submitted_at' => now(),
                'status' => 'turned_in',
            ]);
        } else {
            Submission::create([
                'post_id' => $post->id,
                'student_id' => $activeUser->id,
                'content' => $request->content,
                'status' => 'turned_in',
                'submitted_at' => now(),
            ]);
        }

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // Teacher Grade Submission
    public function gradeSubmission(Submission $submission, Request $request)
    {
        $activeUser = $this->getActiveUser();
        $classroom = $submission->assignment->classroom;

        if ($classroom->teacher_id !== $activeUser->id) {
            return back()->with('error', 'Anda tidak memiliki hak untuk menilai tugas ini!');
        }

        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'status' => 'graded',
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
