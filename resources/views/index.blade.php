@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="mb-3">Фильтр курсов валют</h3>
                    <form class="mb-3" method="GET" action="{{ url('/') }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <select required class="form-control" name="valuteID">
                                    <option value="">Выбрать валюту</option>
                                    @foreach($data as $item)
                                        <option {{request()->valuteID == $item['valuteID'] ? 'selected' : null}} value="{{$item['valuteID']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input value="{{request()->date_req1}}" required placeholder="Дата от" class="datepicker form-control" type="text" name="date_req1">
                            </div>
                            <div class="col-sm-3">
                                <input value="{{request()->date_req2}}" required placeholder="Дата до" class="datepicker form-control" type="text" name="date_req2">
                            </div>
                            <div class="col-sm-3">
                                <input value="Фильтр" type="submit" class="btn btn-primary w-100">
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-sm-6">
                            @if(!empty($output))
                                <table class="table mb-3">
                                    <tr>
                                        <td><strong>Идентификатор</strong></td>
                                        <td><strong>Числовой код</strong></td>
                                        <td><strong>Буквенный код</strong></td>
                                        <td><strong>Имя</strong></td>
                                        <td><strong>Значение</strong></td>
                                        <td><strong>Дата публикации</strong></td>
                                    </tr>
                                    @foreach($output as $item) 
                                        <tr>
                                            <td>{{$item['valuteID']}}</td>
                                            <td>{{$item['numCode']}}</td>
                                            <td>{{$item['сharCode']}}</td>
                                            <td>{{$item['name']}}</td>
                                            <td>{{$item['value']}}</td>
                                            <td>{{date('d.m.Y', strtotime($item['date']))}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>
</div>
@endsection

@section("scripts")


<script>

$( function() {
    $( ".datepicker" ).datepicker(
        {
            dateFormat: "yy-mm-dd"
        }
    );    

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'line',
        data: {
        labels: [
            @foreach($output as $item) 
            '{{date('d.m.Y', strtotime($item['date']))}}',
            @endforeach
        ],
        datasets: [{
            label: 'Data',
            data: [
                @foreach($output as $item) 
                {{str_replace(",", ".", $item['value'])}},
                @endforeach
            ],
            borderWidth: 1
        }]
        },
    });

} );
    
</script>

@endsection
