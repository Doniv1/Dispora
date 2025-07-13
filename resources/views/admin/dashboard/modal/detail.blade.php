<table class="table table-bordered">
    <tr>
        <td style="max-width : 50px">Reporter</td>
        <td>{{ $result->user->name }}</td>
    </tr>
    <tr>
        <td style="max-width : 50px">Case Category</td>
        <td>{{ $result->category->name }}</td>
    </tr>
    <tr>
        <td style="max-width : 50px">Name 1</td>
        <td>{{ $result->name_1 }}</td>
    </tr>
    <tr>
        <td style="max-width : 50px">Name 2</td>
        <td>{{ $result->name_2 }}</td>
    </tr>
    <tr>
        <td style="max-width : 50px">Place</td>
        <td>{{ $result->place }}</td>
    </tr>
    <tr>
        <td style="max-width : 50px">Date of Incident</td>
        <td>{{ date('d-M-Y',strtotime($result->date)) }}</td>
    </tr>
    <tr>
        <td style="max-width : 50px">Report Date</td>
        <td>{{ date('d-M-Y H:i',strtotime($result->created_at)) }}</td>
    </tr>
    <tr>
        <td style="max-width : 50px">Description</td>
        <td>{{ $result->description }}</td>
    </tr>
</table>

@if($bukti && $bukti->isNotEmpty())
<div class="w-100 row">
    @foreach($bukti AS $row)
        <div class="col-sm-4 mx-2 my-3">
            <div class="background-partisi rounded cursor-pointer" onclick="preview_image(this)" style="width : 120px; height : 120px;background-image : url({{ image_check($row->file,'report') }} )"></div>
        </div>
    @endforeach
</div>
@endif