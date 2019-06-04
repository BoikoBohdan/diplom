require('dotenv').config()
const Exporter = () => {
  let filename = localStorage.getItem('file_name')
  let anchor = document.createElement("a");
  document.body.appendChild(anchor);
  let file = process.env.MIX_PUBLIC_URL + '/api/admin/export?export=' + filename;

  let headers = new Headers();
  headers.append('Authorization', `Bearer ${localStorage.getItem('token')}`);

  fetch(file, { headers })
    .then(response => response.blob())
    .then(blobby => {
      let objectUrl = window.URL.createObjectURL(blobby);
      anchor.href = objectUrl;
      anchor.download = filename + '.xlsx';
      anchor.click();
      window.URL.revokeObjectURL(objectUrl);
    });
}
export default Exporter