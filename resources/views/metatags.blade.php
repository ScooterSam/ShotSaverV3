<meta content="{{request()->fullUrl()}}" property="og:url" />

@if($file->type == 'image')
	<meta property="twitter:card" content="summary_large_image" />
	<meta property="og:image" content="{{$file->url}}"/>
	<meta property="og:image:type" content="{{$file->mime_type}}"/>
	<meta property="og:type" content="image"/>
	@if($file->meta)
		<meta property="og:image:width" content="{{$file->meta->width}}" />
		<meta property="og:image:height" content="{{$file->meta->height}}" />
	@endif
	<meta content="article" property="og:type" />
@endif

<meta property="twitter:site" content="@ShotSaver" />
<meta property="twitter:title" content="{{$file->name}}" />
<meta property="og:site_name" content="ShotSaver" />
<meta property="twitter:description" content="{{ucfirst($file->type)}} uploaded to ShotSaver by {{$file->user->name}}" />
<meta property="description" content="{{ucfirst($file->type)}} uploaded to ShotSaver by {{$file->user->name}}"/>
<meta property="og:description" content="{{ucfirst($file->type)}} uploaded to ShotSaver by {{$file->user->name}}" />

@if($file->type == 'video')
	<meta property="og:type" content="video.other"/>
	<meta property="og:image" content="{{$file->thumb}}" />
	<meta property="og:image:secure_url" content="{{$file->thumb}}" />
	<meta property="og:image:type" content="{{$file->mime_type}}" />
	@if($file->meta)
		<meta property="og:image:width" content="{{$file->meta->width}}"/>
		<meta property="og:image:height" content="{{$file->meta->height}}"/>
	@endif

	<meta property="og:updated_time" content="{{$file->created_at}}" />
	<meta property="og:video" content="{{$file->url}}"/>
	<meta property="og:video:url" content="{{$file->url}}"/>
	<meta property="og:video:secure_url" content="{{$file->url}}"/>
	<meta property="og:video:type" content="{{$file->mime_type}}"/>
	@if($file->meta)
		<meta property="og:video:width" content="{{$file->meta->width}}"/>
		<meta property="og:video:height" content="{{$file->meta->height}}"/>
	@endif
	<meta property="twitter:card" content="player"/>
	<meta property="twitter:site" content="@ShotSaver"/>
	<meta property="twitter:image" content="{{$file->thumb}}"/>
	@if($file->meta)
		<meta property="twitter:player:width" content="{{$file->meta->width}}"/>
		<meta property="twitter:player:height" content="{{$file->meta->height}}"/>
	@endif
	<meta property="twitter:player:stream" content="{{$file->url}}"/>
	<meta property="twitter:player:stream:content_type" content="{{$file->mime_type}}"/>
@endif
