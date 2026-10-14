<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HR Assessment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #eef2f6;
            --shadow-light: #ffffff;
            --shadow-dark: #a6b4c8;
            --text-color: #1e293b;
            --btn-color: #0284c7;
            --btn-gradient: linear-gradient(135deg, #0284c7, #38bdf8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            color: var(--text-color);
        }

        .container {
            position: relative;
            width: 800px;
            height: 550px;
            background-color: var(--bg-color);
            border-radius: 20px;
            box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light);
            overflow: hidden;
        }

        .form-container {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 50%;
            z-index: 2;
        }

        form {
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .social-container {
            margin: 15px 0;
            width: 100%;
        }

        .google-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 14px 15px;
            margin: 10px 0;
            border-radius: 12px;
            background-color: var(--bg-color);
            color: var(--text-color);
            text-decoration: none;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            border: 2px solid transparent;
        }

        .google-btn:hover {
            box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
            transform: translateY(-1px);
        }

        .google-btn i {
            margin-right: 10px;
        }

        .google-btn:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            transform: translateY(1px);
        }

        /* Legacy social container a styles if needed elsewhere */
        .social-circle {
            border: none;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 5px;
            height: 40px;
            width: 40px;
            border-radius: 50%;
            color: var(--text-color);
            text-decoration: none;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
            transition: 0.2s;
        }

        span {
            font-size: 12px;
            margin-bottom: 15px;
        }

        input, select {
            background-color: var(--bg-color);
            border: 2px solid transparent;
            padding: 14px 18px;
            margin: 10px 0;
            width: 100%;
            border-radius: 12px;
            outline: none;
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
            color: var(--text-color);
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .sign-up-container input {
            padding: 10px 15px;
            margin: 6px 0;
            font-size: 13px;
        }

        input:focus, select:focus {
            border: 2px solid var(--btn-color);
            box-shadow: inset 2px 2px 4px var(--shadow-dark), inset -2px -2px 4px var(--shadow-light);
        }



        button {
            border-radius: 25px;
            border: none;
            background: var(--btn-gradient);
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 600;
            padding: 14px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 25px;
            cursor: pointer;
            box-shadow: 4px 4px 12px rgba(79, 70, 229, 0.4);
            transition: all 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 6px 6px 15px rgba(79, 70, 229, 0.5);
        }

        button:active {
            transform: scale(0.95);
        }

        button.ghost {
            background: transparent;
            color: #ffffff;
            border: 2px solid #ffffff;
            box-shadow: none;
        }

        button.ghost:hover {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: none;
            transform: translateY(-2px);
        }

        /* OVERLAY PANEL */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            z-index: 100;
        }

        .overlay {
            background-color: var(--bg-color);
            position: relative;
            left: 0;
            height: 100%;
            width: 100%;
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 100%;
            z-index: 2;
            color: var(--text-color);
        }

        .alert-error {
            background-color: rgba(255, 99, 71, 0.2);
            color: #d8000c;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            width: 100%;
            font-size: 12px;
            text-align: left;
            box-shadow: inset 2px 2px 5px var(--shadow-dark), inset -2px -2px 5px var(--shadow-light);
        }

        /* Neumorphic Circle Indents */
        .circle-indent {
            position: absolute;
            border-radius: 50%;
            box-shadow: inset 8px 8px 16px var(--shadow-dark), 
                        inset -8px -8px 16px var(--shadow-light);
            background-color: var(--bg-color);
            z-index: 0;
        }

        .circle-top {
            width: 250px;
            height: 250px;
            top: -100px;
            left: -50px;
        }

        .circle-bottom {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -50px;
        }

        .overlay-content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body>

<div class="container" id="container">
    <div class="form-container">
        <form method="POST" action="<?php echo e(route('login.store')); ?>">
            <?php echo csrf_field(); ?>
            <h1 style="color: var(--btn-color); margin-bottom: 10px;">Welcome to SORA</h1>
            <span style="margin-bottom: 20px; color: #64748b;">Sign in to continue your Employee Journey</span>
            
            <?php if($errors->any()): ?>
                <div class="alert-error" style="color: red; margin-bottom: 15px; font-size: 14px;">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>


            <input type="text" name="npk" value="<?php echo e(old('npk')); ?>" placeholder="NPK (Nomor Pokok Karyawan)" required autofocus />
            
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password" />
            <a href="#" style="font-size: 12px; color: #4a4a4a; text-decoration: none; margin-top: 10px;">Forgot your password?</a>

            <button type="submit">Sign In</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel">
                <div class="circle-indent circle-top" style="left: auto; right: -50px;"></div>
                <div class="circle-indent circle-bottom" style="right: auto; left: -50px;"></div>
                <div class="overlay-content">
                    <h1 style="font-size: 3.5rem; letter-spacing: 4px; margin-bottom: 5px; color: var(--btn-color);">SORA</h1>
                    <h3 style="font-weight: 400; font-size: 1.2rem; letter-spacing: 2px; margin-bottom: 25px; text-transform: uppercase;">Employee Journey</h3>
                    <p style="font-size: 14px; line-height: 1.6; max-width: 80%; margin: 0 auto; color: #64748b;">
                        Empowering your career path, personal growth, and continuous learning experiences.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JS removed
</script>

</body>
</html>

<?php /**PATH C:\laragon\www\radarhc\resources\views/auth/login.blade.php ENDPATH**/ ?>