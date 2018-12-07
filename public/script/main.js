(function(){
const formforCreate = document.querySelector('.create-item');
const btnCreate = document.querySelector('.create-item button');

btnCreate.onclick = function () {
  const data = {
    text: document.querySelector('.create-item input[type=text]').value,
    ready: document.querySelector('.create-item input[type=checkbox]').checked
  }
  
  createItem(data) 
}

function createItem({ text, ready}) {
  fetch("/createItem", {
    method: "POST",
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      text,
      ready
    })
  })
  .then(() => {
    document.location.reload(true);
  })
  .catch(error => console.error(error.message));
}




})();