<div
    x-data="{
        model: @entangle($attributes->wire('model')),
    }"
    x-init="
        select2 = $($refs.select)
            .not('.select2-hidden-accessible')
            .select2({
                allowClear: true
            });
        select2.on('select2:select', (event) => {
            var element = event.target.id;
            var idEmit = 'change'+element.charAt(0).toUpperCase() + element.substring(1).toLowerCase()
            //console.log()
            //console.log(event.params.data.element.attributes.value.nodeValue)
            //console.log(event.params.data.title)
            if(event.params.data.element.attributes.id){
                Livewire.emit(idEmit, {id:event.params.data.element.attributes.id.nodeValue, value:event.params.data.title})
            } else {
                Livewire.emit(idEmit, {id:null, value:null})
            }
            //model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value);
            if (event.target.hasAttribute('multiple')) { 
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); 
            } else { 
                model = event.params.data.id 
            }
        });
        select2.on('select2:unselect', (event) => {
            if (event.target.hasAttribute('multiple')) { 
                model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); 
            } else { 
                model = event.params.data.id 
            }
        });
        
        $watch('model', (value) => {
            select2.val(value).trigger('change');
        });
    "
    wire:ignore
>
    <select x-ref="select" {{ $attributes->merge(['class' => 'form-select']) }}>
        {{ $slot }}
    </select>
</div>