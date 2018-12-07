(function(){
const btnCreate = document.querySelector('.create-item button');

const menuBtn = document.querySelectorAll('.menu button');
const form = document.querySelector('.create-item');
const list = document.querySelector('table');

const deleteBtn = document.querySelectorAll('.delete');

btnCreate.onclick = function () {
  const data = {
    text: document.querySelector('.create-item input[type=text]').value,
    ready: document.querySelector('.create-item input[type=checkbox]').checked
  }
  
  createItem(data) 
}

function createItem({ text, ready}) {
  fetch("/item", {
    method: "POST",
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      text,
      ready
    })
  })
  .then(res => {
    if(res.ok) document.location.reload(true);
  })
  .catch(error => console.error(error.message));
}

menuBtn[0].onclick = function () {
  form.style.display = 'none';
  list.style.display = 'table';
}

menuBtn[1].onclick = function () {
  form.style.display = 'block';
  list.style.display = 'none';
}

for (let i = 0; i < deleteBtn.length; i++) {
  deleteBtn[i].onclick = function (e) {
    fetch("/item", {
      method: "DELETE",
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: e.target.attributes.id.value
      })
    })
    .then(res => {
      if(res.ok) document.location.reload(true);
    })
    .catch(error => console.error(error.message));
  }
}



})();