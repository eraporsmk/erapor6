<div>
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
        <input
        x-data
        x-ref="input"
        x-init="
        moment.locale('id');
        new Pikaday({ 
            field: $refs.input, 
            format: 'LL', 
            onOpen() {
                this.setDate(moment($refs.input.value).format('YYYY-MM-DD')) 
            },
            onSelect: function() {
                this.setDate(moment($refs.input.value).format('YYYY-MM-DD')) 
                //console.log(this.getMoment().format('Do MMMM YYYY'));
            },
            i18n: {
                previousMonth : 'Previous Month',
                nextMonth     : 'Next Month',
                months        : ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                weekdays      : ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
                weekdaysShort : ['Min','Sen','Sel','Rab','Kam','Jum','Sab']
            }
        })
        $watch('model', (value) => {
            console.log(value)
        })
        "
        type="text"
        readonly
        {{ $attributes }}
        >
    </div>
</div>