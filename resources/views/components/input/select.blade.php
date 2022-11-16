<div 
	x-data
	x-init="() => {
	var choices = new Choices($refs.{{ $attributes['prettyname'] }}, {
		itemSelectText: '',
	});
	choices.passedElement.element.addEventListener(
	  'change',
	  function(event) {
			values = event.detail.value;
		    @this.set('{{ $attributes['wire:model'] }}', values);
	  },
	  false,
	);
	let selected = parseInt(@this.get{!! $attributes['selected'] !!}).toString();
	choices.setChoiceByValue(selected);
	}"
	>
    <select id="{{ $attributes['prettyname'] }}" wire-model="{{ $attributes['wire:model'] }}" wire:change="{{ $attributes['wire:change'] }}" x-ref="{{ $attributes['prettyname'] }}">
    	<option value="">{{ isset($attributes['placeholder']) ? $attributes['placeholder'] : '-- Select --' }}</option> 
    	@if(count($attributes['options'])>0)
	    	@foreach($attributes['options'] as $key=>$option)
	    		<option value="{{$key}}" >{{$option}}</option>
	    	@endforeach
    	@endif
    </select>
</div>