<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Form;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    public function create()
    {
        return view('/form/create_form');
    }

    public function store(Request $request)
    {
        
        $rules = [
            
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|numeric',
            'address' => 'required|string|max:255',
            'party_type' => 'required|string|max:255',
            'daerah_party' => 'required|string|max:255',
            
        ];

        $message = [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'phone.required' => 'Please enter your phone number',
            'address.required' => 'Please enter your address',
            'party_type.required' => 'Please enter the party type',
            'daerah_party.required' => 'Please enter the party region',
        ];

        $validatedData = $request->validate($rules, $message);
        
        try {
            $data = [
                "user_id" => auth()->user()->id,
                "name" => $validatedData["name"],
                "email" => $validatedData["email"],
                "phone" => $validatedData["phone"],
                "address" => $validatedData["address"],
                "party_type" => $validatedData["party_type"],
                "daerah_party" => $validatedData["daerah_party"],
                
            ];

            Form::create($data);
            $message = "Order has been created successfully!";

            // Assuming you have a flash message system in place
            // This function needs to be implemented according to your flash message library
            session()->flash('message', $message);
            session()->flash('success', true);
            
            return redirect('/');
        } catch (\Illuminate\Database\QueryException $e) {
            echo ''. $e->getMessage();
            Log::error('Database Query Exception: ' . $e->getMessage());
            return abort(500);
            
        }
    }
    // public function showHistory()
    // {
    //     $forms = Form::orderBy('created_at', 'desc')->paginate(10); // Mengambil history form, misalnya 10 form per halaman
    //     $title = 'History';
    //     return view('form/history_form', compact('title','forms'));
    // }
    public function showHistory( Request $request)
    {
        // Ambil history form yang terkait dengan user yang sedang login
        // $title = 'History';
        // $forms = Form::where('user_id', auth()->user()->id);
        
        // return view('/form/history_form', compact('title','forms'));

        // Ambil semua form yang terkait dengan user yang sedang login
        $userId = Auth::id(); // Mengambil user_id dari user yang sedang login
        $username = Auth::user()->username;
        $title = 'History of username:'.$username;
        // $forms = Form::where('name', $userId)->get(); // Ambil semua form dengan user_id yang sesuai

        $forms = Form::all(); // Mengambil history form, misalnya 10 form per halaman

        return view('/form/history_form', compact('title','forms'));
    }
}
