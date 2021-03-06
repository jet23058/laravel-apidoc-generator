<!-- START_{{$route['id']}} -->
@if($route['title'] != '')## {{ $route['title']}}
@else## {{$route['uri']}}@endif
@if($route['authenticated'])<small style="
  padding: 1px 9px 2px;
  font-weight: bold;
  white-space: nowrap;
  color: #ffffff;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
  background-color: #3a87ad;">Requires authentication</small>@endif
@if($route['description'])

{!! $route['description'] !!}
@endif

> Example request:

```bash
curl -X {{$route['methods'][0]}} {{$route['methods'][0] == 'GET' ? '-G ' : ''}}"{{ trim(config('app.docs_url') ?: config('app.url'), '/')}}/{{ ltrim($route['uri'], '/') }}" \
    -H "Accept: application/json"@if(count($route['headers'])) \
@foreach($route['headers'] as $header => $value)
    -H "{{$header}}: {{$value}}" @if(! ($loop->last))\
    @endif
@endforeach
@endif
@if(count($route['parameters'])) \
@foreach($route['parameters'] as $attribute => $parameter)
    -d "{{$attribute}}"={{$parameter['value']}} @if(! ($loop->last))\
    @endif
@endforeach
@endif

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "{{ rtrim(config('app.docs_url') ?: config('app.url'), '/') }}/{{ ltrim($route['uri'], '/') }}",
    "method": "{{$route['methods'][0]}}",
    @if(count($route['parameters']))
"data": {!! str_replace("\n}","\n    }", str_replace('    ','        ',json_encode(array_combine(array_keys($route['parameters']), array_map(function($param){ return $param['value']; },$route['parameters'])), JSON_PRETTY_PRINT))) !!},
    @endif
"headers": {
        "accept": "application/json",
@foreach($route['headers'] as $header => $value)
        "{{$header}}": "{{$value}}",
@endforeach
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

@if(in_array('GET',$route['methods']) || (isset($route['showresponse']) && $route['showresponse']))
> Example response:

```json
@if(is_object($route['response']) || is_array($route['response']))
{!! json_encode($route['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@else
{!! json_encode(json_decode($route['response']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
@endif
```
@endif

### HTTP Request
@foreach($route['methods'] as $method)
`{{$method}} {{$route['uri']}}`

@endforeach
@if(count($route['parameters']))
#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
@foreach($route['parameters'] as $attribute => $parameter)
    {{$attribute}} | {{$parameter['type']}} | @if($parameter['required']) required @else optional @endif | {!! $parameter['description'] !!}
@endforeach
@endif

<!-- END_{{$route['id']}} -->
