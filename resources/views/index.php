<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
</head>

<body>
    <h1>{{ $heading }}</h1>

    @if($showList)
    <ul>
        @foreach($items as $item)
        <li>{{ $item }}</li>
        @endforeach
    </ul>
    @else
    <p>No items to display.</p>
    @endif
</body>

</html>