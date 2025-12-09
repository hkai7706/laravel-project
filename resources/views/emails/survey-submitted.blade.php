<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Love Survey Submission</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                    
                    <tr>
                        <td style="background: linear-gradient(135deg, #FF1493 0%, #FF69B4 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: bold;">
                                💕 Love Survey Submission
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px; background-color: #FFF0F5;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 15px; background-color: #ffffff; border-radius: 8px; margin-bottom: 20px;">
                                        <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">
                                            <strong style="color: #FF1493;">Participant Name:</strong>
                                        </p>
                                        <p style="margin: 0; color: #333; font-size: 18px; font-weight: bold;">
                                            {{ $userName }}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px; background-color: #ffffff; border-radius: 8px;">
                                        <p style="margin: 0; color: #666; font-size: 14px;">
                                            <strong style="color: #FF1493;">Submitted:</strong> {{ $submittedAt }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <h2 style="color: #FF1493; font-size: 24px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #FFE4E1;">
                                📋 Survey Responses
                            </h2>

                            @foreach($responses as $response)
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px; background-color: #FFF0F5; border-radius: 8px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; color: #FF1493; font-weight: bold; font-size: 16px;">
                                            Question {{ $response['number'] }}
                                        </p>
                                        <p style="margin: 0 0 15px 0; color: #333; font-size: 15px; line-height: 1.5;">
                                            {{ $response['question'] }}
                                        </p>

                                        <div style="background-color: #FF1493; color: #ffffff; padding: 12px 15px; border-radius: 6px; margin-top: 10px;">
                                            <p style="margin: 0; font-size: 14px;">
                                                <strong>Selected Answer:</strong> {{ $response['selected_option'] }}
                                            </p>
                                            <p style="margin: 5px 0 0 0; font-size: 15px; font-weight: 600;">
                                                {{ $response['answer'] }}
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #FFE4E1; padding: 25px 30px; text-align: center;">
                            <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">
                                This email was generated automatically from the Love Survey application.
                            </p>
                            <p style="margin: 0; color: #999; font-size: 12px;">
                                💖 Thank you for using our survey platform 💖
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>