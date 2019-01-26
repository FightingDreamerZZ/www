@extends('layouts.main')

@section('content')
<div id="main" >
    <h1>List <small>Employee Detail</small></h1>
    <div id="displaytable">
    
    <table  class="table table-hover">
       <thead>
  <tr>
     <th>Employee Name</th>
     <th>Employee Id </th>
       <th>Department</th>
     <th>Email Address</th>
          <th>Phone</th>

  </tr>
  </thead>
       <thead>
  <tr>
     <th>Employee Name</th>
     <th>Employee Id </th>
  
  <tbody class="table-hover">
  <tr>
     <td>Jhon</td>
     <td>emp109</td>
     <td>IT</td>
     <td>12 Main Street</td>
      <td>1234567890</td>

  </tr>
  <tr>
      <td></td>
      <td></td>
      <td></td>
     <td></td>
          <td></td>

  </tr>
  </tbody>
</table>
    
    </div></div>

@endsection