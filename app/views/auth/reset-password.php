<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Legal Document Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Create new password
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Please enter your new password below.
                </p>
            </div>
            <?php if ($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?php foreach ($errors->all() as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <form class="mt-8 space-y-6" action="/reset-password" method="POST">
                <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="token" value="<?= $token ?>">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="password" class="sr-only">New Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="New Password">
                    </div>
                    <div>
                        <label for="password_confirmation" class="sr-only">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="appearance-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                               placeholder="Confirm New Password">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>