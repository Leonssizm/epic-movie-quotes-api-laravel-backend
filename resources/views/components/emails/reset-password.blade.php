<!DOCTYPE html>

<body
    style='background:linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%); height:100vh; width:100%;'>

    <div style='text-align:center; margin-top:150px;'>
        <img src="{{ $message->embed(public_path('/assets/images/citation-image.png')) }}" style='margin-top:50px;' />
        <h1
            style="font-style: normal; font-weight: 500; font-size: 12px; line-height: 150%; text-transform: uppercase; color: #DDCCAA;">
            MOVIE QUOTES</h1>
    </div>
    <p style='color: #DDCCAA; margin-left:100px;'>Hello, {{$name}}</p>
    <p style='color: #FFFFFF; margin-left:100px;'>Thanks for joining Movie quotes! We really appreciate it. Please click
        the button below to verify password change</p>

    <a href="{{$verificationLink}}"
        style="margin-left:100px; margin-top:50px; font-family: 'Helvetica Neue'; text-align:center; font-style: normal; font-weight: 400; font-size: 16px; line-height: 150%; color: #FFFFFF; background: #E31221; border-radius: 4px; width: 128px; height: 38px; display: inline-block; text-align: center; text-decoration: none;">Change
        password</a>

    <p style='color: #FFFFFF; margin-left:100px; margin-top:50px;'>If clicking doesn't work, you can try copying and
        pasting it to your
        browser:</p>

    <a href="{{$verificationLink}}" style="margin-left:100px; color: #DDCCAA;">{{$verificationLink}}</a>

    <p style='color: #FFFFFF; margin-left:100px; margin-top:50px; margin-top:50px;'>If you have any problems, please
        contact us:
        support@moviequotes.ge
    </p>

    <p style='color: #FFFFFF; margin-left:100px; margin-top:50px;'>MovieQuotes Crew</p>


</body>