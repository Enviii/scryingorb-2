@extends('app')

@section('content')

<div class="container">
	<div class="row">

		{{-- <ul> --}}
			@foreach ($champions['data'] as $champion)

				<div class="col-md-2">
					<a href="{{URL::action('ChampionController@show', $champion['id'])}}" class="thumbnail">
						<img src="http://ddragon.leagueoflegends.com/cdn/5.3.1/img/champion/{{$champion['image']['full']}}" alt="{{$champion['name']}}">
					</a>
				</div>

			@endforeach
		{{-- </ul> --}}

	</div>
</div>

@endsection