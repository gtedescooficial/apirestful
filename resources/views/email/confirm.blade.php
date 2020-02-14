Olá {{ $user->name }}
Sua conta de e-mail foi alterada.
Agora é so verificar a conta no seguinte enlace {{ route('verify',$user->verification_token)}}