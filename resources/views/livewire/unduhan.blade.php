<div>
    @include('panels.breadcrumb')
    <div class="content-body"> 
        <div class="row match-height">
          @foreach ($collection as $item)
          <div class="col-lg-4 col-12">
            <div class="row match-height">
              @foreach ($item['collection'] as $item)
              <div class="col-lg-6 col-md-3 col-6">
                <div class="card">
                  <div class="card-body p-1 text-center">
                    <a href="/downloads/{{$item['url']}}" target="_blank">
                      <h6>{!! $item['title'] !!}</h6>
                      <img src="{{asset('images/pdf.jpg')}}" alt="Unduh" class="img-fluid">
                    </a>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
    </div>
</div>
