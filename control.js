function loadPage(url) {
  var contentContainer = document.getElementById('contentContainer');
  var iframe = document.createElement('iframe');
  iframe.src = url;
  iframe.style.position = 'absolute';
  iframe.style.left = '149px';
  iframe.style.top = '248px';
  iframe.style.width = '1335px';
  iframe.style.height = '600px';
   iframe.style.border = '0px solid #f1965f';

  // Replace the existing content with the iframe
  contentContainer.innerHTML = '';
  contentContainer.appendChild(iframe);
}
