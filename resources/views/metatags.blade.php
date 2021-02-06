<meta content="{{request()->fullUrl()}}" property="og:url" />

@if($file->type == 'image')
	<meta name="twitter:card" content="summary_large_image" />
	<meta property="og:image" content="{{$file->url}}">
	<meta property="og:image:type" content="{{$file->mime_type}}">
	<meta property="og:type" content="image">
	<meta property="og:image:width" content="{{$file->meta->width}}" />
	<meta property="og:image:height" content="{{$file->meta->height}}" />
	<meta content="article" property="og:type" />
@endif

<meta name="theme-color" content="#0084da">
<meta name="twitter:site" content="@ShotSaver" />
<meta name="twitter:title" content="ShotSaver" />
<meta name="og:site_name" content="ShotSaver" />
<meta name="twitter:description" content="{{ucfirst($file->type)}} uploaded to ShotSaver by {{$file->user->name}}" />
<meta name="description" content="{{ucfirst($file->type)}} uploaded to ShotSaver by {{$file->user->name}}">
<meta property="og:description" content="{{ucfirst($file->type)}} uploaded to ShotSaver by {{$file->user->name}}" />

@if($file->type == 'video')
	<meta property="og:type" content="video.other">
	<meta property="og:image" content="{{$file->thumb}}" />
	<meta property="og:image:secure_url" content="{{$file->thumb}}" />
	<meta property="og:image:type" content="{{$file->mime_type}}" />
	<meta property="og:image:width" content="{{$file->meta->width}}">
	<meta property="og:image:height" content="{{$file->meta->height}}">

	<meta property="og:updated_time" content="{{$file->created_at}}" />
	<meta property="og:video" content="{{$file->url}}">
	<meta property="og:video:url" content="{{$file->url}}">
	<meta property="og:video:secure_url" content="{{$file->url}}">
	<meta property="og:video:type" content="{{$file->mime_type}}">
	<meta property="og:video:width" content="{{$file->meta->width}}">
	<meta property="og:video:height" content="{{$file->meta->height}}">
	<meta name="twitter:card" content="player">
	<meta name="twitter:site" content="@ShotSaver">
	<meta name="twitter:image" content="{{$file->thumb}}">
	<meta name="twitter:player:width" content="{{$file->meta->width}}">
	<meta name="twitter:player:height" content="{{$file->meta->height}}">
	<meta name="twitter:player:stream" content="{{$file->url}}">
	<meta name="twitter:player:stream:content_type" content="{{$file->mime_type}}">
@endif
