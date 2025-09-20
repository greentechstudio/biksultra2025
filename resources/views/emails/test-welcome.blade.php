<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Email</title>
</head>
<body>
    <h1>Test Email</h1>
    <p>User: {{ $user->name ?? 'Test User' }}</p>
    <p>Email: {{ $user->email ?? 'test@example.com' }}</p>
    <p>Event: {{ $event_name ?? 'Test Event' }}</p>
    <p>Organizer: {{ $event_organizer ?? 'Test Organizer' }}</p>
    <p>Payment: {{ $payment_amount ?? 0 }}</p>
</body>
</html>