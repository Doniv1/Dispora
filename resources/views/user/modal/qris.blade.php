<div class="px-5 py-7">
    <div class="w-100 background-partisi-contain" style="height: 500px;background-image: url({{ image_check($qris->image,'qris/'.date('dmY',strtotime($sesi->start_date))) }})"></div>
    <button type="button" onclick="close_modal_qris()" class="w-100 mt-4 btn btn-primary">Tutup</button>
</div>
