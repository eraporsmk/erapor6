<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        @foreach($all_sikap as $sikap)
                        <th width="20%" class="text-center">{{$sikap->butir_sikap}}</th>
                        @endforeach
                    </thead>
                    <tbody>
                    <tr>
                    @foreach($all_sikap as $sikap)
                        <td>
                        <ul style="padding-left:10px;">
                        @foreach($sikap->sikap as $subsikap)
                        <li>{{$subsikap->butir_sikap}}</li>
                        @endforeach
                        </ul>
                        </td>
                    @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
