<!DOCTYPE html>
<html>
<head>
    <title>All Events</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        h1 { text-align: center; margin-bottom: 40px; }
        .event { background: #fff; border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
        .event img { max-width: 200px; display: block; margin-top: 10px; }
        .event h2 { margin: 0 0 10px 0; }
        .event p { margin: 5px 0; }
    </style>
</head>
<body>

<h1>All Events</h1>

@if(isset($events) && $events->count() > 0)
    @foreach($events as $event)
        <div class="event">
            <h2>{{ $event->title }}</h2>
            <p>{{ $event->description }}</p>
            <p><strong>Date:</strong> {{ $event->event_date }}</p>
            <p><strong>Time:</strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
            <p><strong>Location:</strong> {{ $event->location }}</p>
            <p><strong>Price:</strong> ${{ $event->price }}</p>
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
            @endif
        </div>
    @endforeach
@else
    <p>No events available at the moment.</p>
@endif

</body>
</html>