<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Essential Life</title>
        <style>
            html,
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: Averta, sans-serif;
                line-height: 1.6;
                color: #49586d;
                display: flex;
                align-items: center;
                justify-content: center;
            } 

            main {
                max-width: 600px;
                text-align: center;
                padding: 30px;
            }

            a {
                color: #3751ff;
            }

            a:hover {
                color: #0425ff;
            }

            h1 {
                font-size: 1rem;
            }
        </style>
    <body>
        <main>
            <p>
                <img src="/images/logo-el.png" width="150" alt="">
            </p>
            <p>Redirecting you to the Essential Life app...</p>
            <br>
            <h1><strong>You must open this link on a device which has the Essential Life app installed.</strong></h1>
            <p>
                Don't have the app? <br>
                <a href="https://app.essentiallife.com/">Install the Essential Life app &rarr;</a>
            </p>
            <p>If you have the app installed, but the redirect is not working, <a href="{{ $redirect_url }}">continue to the app</a>.</p>
        </main>

        <script>
            var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);

            if (iOS) {
                document.location.href = '{{ $redirect_url }}';
                window.close;
            }
        </script>
    </body>
</html>