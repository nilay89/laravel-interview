@extends('default')

@section('content')


    @include('prob-notice')


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('prizes.create') }}" class="btn btn-info">Create</a>
                </div>
                <h1>Prizes</h1>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Probability</th>
                            <th>Awarded</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prizes as $prize)
                            <tr>
                                <td>{{ $prize->id }}</td>
                                <td>{{ $prize->title }}</td>
                                <td>{{ $prize->probability }}</td>
                                <td> {{ $prize->awarded_count }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('prizes.edit', [$prize->id]) }}" class="btn btn-primary">Edit</a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['prizes.destroy', $prize->id]]) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3>Simulate</h3>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['method' => 'POST', 'route' => ['simulate']]) !!}
                        <div class="form-group">
                            {!! Form::label('number_of_prizes', 'Number of Prizes') !!}
                            {!! Form::number('number_of_prizes', 50, ['class' => 'form-control']) !!}
                        </div>
                        {!! Form::submit('Simulate', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>

                    <br>

                    <div class="card-body">
                        {!! Form::open(['method' => 'POST', 'route' => ['reset']]) !!}
                        {!! Form::submit('Reset', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>



    <div class="container  mb-4">
        <div class="row">
            <div class="col-md-6">
                <h2>Probability Settings</h2>
                <canvas id="probabilityChart"></canvas>
            </div>
            <div class="col-md-6">
                <h2>Actual Rewards</h2>
                <canvas id="awardedChart"></canvas>
            </div>
        </div>
    </div>


@stop

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const titles = @json($prizes->pluck('title'));
            const probabilities = @json($prizes->pluck('probability'));
            const awardedCounts = @json($prizes->pluck('awarded_count'));

            const totalAwarded = awardedCounts.reduce((sum, count) => sum + count, 0);
            const actualProbabilities = awardedCounts.map(count => totalAwarded > 0 ? (count / totalAwarded * 100)
                .toFixed(2) : 0);

            const probabilityChart = new Chart(document.getElementById('probabilityChart'), {
                type: 'doughnut',
                data: {
                    labels: titles.map((title, index) => `${title} (${probabilities[index]}%)`),
                    datasets: [{
                        data: probabilities,

                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    plugins: {
                        datalabels: {
                            formatter: (value, context) => context.chart.data.labels[context.dataIndex],
                        }
                    },
                }
            });

            const awardedChart = new Chart(document.getElementById('awardedChart'), {
                type: 'doughnut',
                data: {
                    labels: titles.map((title, index) => `${title} (${actualProbabilities[index]}%)`),
                    datasets: [{
                        data: actualProbabilities,
                        backgroundColor: [
                            '#32a852', '#36A2EB', '#FFCE56', '#cf1fbd', '#cad65c',
                            '#4BC0C0', '#9966FF', '#FF9F40', '#42263d', '#0e5953'
                        ],
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    plugins: {
                        datalabels: {
                            formatter: (value, context) => context.chart.data.labels[context.dataIndex],
                        }
                    },
                }
            });
        });
    </script>
@endpush
