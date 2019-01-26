@extends('layouts.main')

@section('content')
<div id="main" >    
    <h1>Announcements &nbsp;&nbsp;<small></small></h1>
    <div id="displaytable">
        @foreach($anncs as $annc)
        <table style="width:80%;">
        <tr>
            <td style="width:75%;"><h4>{{$annc->title}}</h4></td>
            <td style="width:15%;border-left:solid 1px gainsboro;padding-left:10px;"><small>Posted by: {{$annc->posted_by}}</small></td>
            
        </tr>
        <tr>
            <td style="width:75%;"><small>Posted on: {{$annc->date}}<br /><br /></small></td>
            <td style="width:15%;border-left:solid 1px gainsboro;padding-left:10px;"><small>*posted to: ...</small></td>
            
        </tr>
        <tr>
            <td style="width:75%;padding-right:20px;">{{$annc->content}}</td>
            <td style="width:15%;border-left:solid 1px gainsboro;">&nbsp;</td>
            
        </tr>
        </table>
        <hr />
        @endforeach
    </div>
</div>

@endsection