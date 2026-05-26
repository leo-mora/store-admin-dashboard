<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistema de Ventas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body{
            min-height: 100vh;
            background: linear-gradient(135deg, #eef4ff 0%, #f4f6fb 45%, #e9f0ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .login-wrapper{
            width: 100%;
            max-width: 430px;
        }

        .brand-box{
            text-align: center;
            margin-bottom: 22px;
        }

        .brand-title{
            font-size: 2rem;
            font-weight: 800;
            color: #0d6efd;
            margin-bottom: 8px;
        }

        .brand-subtitle{
            color: #6c757d;
            margin: 0;
            font-size: 0.98rem;
        }

        .login-card{
            background: #fff;
            border: none;
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.10);
            overflow: hidden;
        }

        .login-header{
            padding: 28px 28px 14px 28px;
            text-align: center;
        }

        .login-title{
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #212529;
        }

        .login-text{
            margin-top: 8px;
            color: #6c757d;
            font-size: 0.95rem;
        }

        .login-body{
            padding: 14px 28px 28px 28px;
        }

        .form-label{
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control{
            border-radius: 14px;
            padding: 12px 14px;
            border: 1px solid #dbe2ea;
            box-shadow: none;
        }

        .form-control:focus{
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.12);
        }

        .btn-login{
            border-radius: 14px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
        }

        .password-wrapper{
            position: relative;
        }

        .toggle-password{
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6c757d;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .invalid-feedback{
            display: block;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">

        <div class="brand-box">
            <div class="brand-title">Sistema de Ventas</div>
            <p class="brand-subtitle">Acceda al panel administrativo</p>
        </div>

        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Iniciar sesión</h1>
                <p class="login-text">Ingrese sus credenciales para continuar</p>
            </div>

            <div class="login-body">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="correo@ejemplo.com"
                        >
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Ingrese su contraseña"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                Ver
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-primary btn-login w-100">
                        Ingresar
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        @if(session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: @json(session('error')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    </script>

</body>
</html> 