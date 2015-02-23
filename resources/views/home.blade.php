@extends('app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in!
				</div>

				@foreach ($champions as $element)

					{{$element->name}} <br>

					
					@foreach ($element->skins as $skin)
						{{$skin->skin_name}} <br>
					@endforeach


				@endforeach

			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		@foreach ($champsOnSale as $champion)
			
			{{{$champion->champion->id}}} - 
			{{{$champion->champion->name}}} - {{{$champion->sale_price}}} - <strike>{{{$champion->original_price}}}</strike>

			<!-- <img src="http://ddragon.leagueoflegends.com/cdn/img/champion/loading/{{{$champion->champion->name}}}_0.jpg" alt="{{{$champion->champion->name}}} Splash Art"> -->

			<!-- http://ddragon.leagueoflegends.com/cdn/img/champion/loading/Aatrox_0.jpg -->
			
			<br>

		@endforeach
	</div>
</div>

@endsection
