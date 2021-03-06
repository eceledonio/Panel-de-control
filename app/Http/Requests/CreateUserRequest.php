<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' =>  ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'bio' => 'required',
            'twitter' => ['nullable', 'url'],
        ];
    }

    public function messages()
    {
      return [
          'name.required' => 'El campo nombre es obligatorio',
          'email.required' => 'El campo Email es obligatorio',
          'email.email' => 'El campo Email debe contener un formato correcto: correo@example.com',
          'email.unique' => 'Este Email ya esta registrado',
          'password.required' => 'El campo contraseña es obligatorio',
          'password.min' => 'El campo contraseña no puede tener menos de 6 caracteres',
          'bio.required'=> 'El campo Bio es obligatorio',
      ];
    }

    public function createUser()
    {
        DB::transaction(function () {
            $data = $this->validated();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]);
            $user->profile()->create([
                'bio' => $data['bio'],
                'twitter' => $data['twitter'],

            ]);
        });
    }
}
