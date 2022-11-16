<!-- BEGIN: Footer-->
<footer class="footer footer-light {{($configData['footerType'] === 'footer-hidden') ? 'd-none':''}} {{$configData['footerType']}}">
  <p class="clearfix mb-0">
    <span class="float-md-start d-block d-md-inline-block mt-25">Hak Cipta &copy;
      <script>document.write(new Date().getFullYear())</script><a class="ms-25" href="https://smk.kemdikbud.go.id/" target="_blank">Direktorat SMK</a>
    </span>
    <span class="float-md-end d-none d-md-block" title="Made with love">Versi {{config('global.app_version')}}<i data-feather="heart"></i></span>
  </p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->
