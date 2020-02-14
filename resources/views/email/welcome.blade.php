Bemvindo {{ $user->name }}
Agora é so verificar a conta no seguinte enlace {{ route('verify',$user->verification_token)}}