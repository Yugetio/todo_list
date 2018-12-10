(function(){

const btnCreate = document.querySelector('.create-item button');

const menuBtn = document.querySelectorAll('.menu button');
const form = document.querySelector('.create-item');
const table = document.querySelector('table');
const tableBody = document.querySelector('table tbody');

let data = [];


menuBtn[0].onclick = showList;
menuBtn[1].onclick = showForm;

function showList() {
  form.style.display = 'none';
  table.style.display = 'table';
}

function showForm() {
  form.style.display = 'block';
  table.style.display = 'none';
}

//create elem...
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
    if(res.ok) {
      reRenderTable();
      showList();
    }
  })
  .catch(error => console.error(error.message));
}



//render table with data
function getAllData() {
  fetch("/items", {
      headers: { 'Content-Type': 'application/json' },
      method: "GET",
  })
  .then(res => {
    if(res.ok) return res.json();
  })
  .then(res => {
    data = res;
    createTable(res);
  })
  .catch(error => console.error(error.message));
}

function createTable(arr = []) {
  
  arr.forEach( (item, i) => {
    createElInTable(i+1, item.text, item.ready)
  });
  setOnclick();
}

function createElInTable(i, text, ready) {
	const tr = document.createElement('tr');
	const indexTd = document.createElement('td');
  const textTd = document.createElement('td');
  const EditTd = document.createElement('td');
  const DeleteTd = document.createElement('td');
  
	indexTd.innerHTML = i;
  textTd.innerHTML = text;

  if (ready) {
    tr.classList.add('ready')
  }

  const btnEdit = document.createElement('button');
  btnEdit.classList.add("btn", "btn-primary", "edit");
  btnEdit.dataset.toggle = 'modal';
  btnEdit.dataset.target = '#exampleModal';

  btnEdit.innerHTML = 'Edit';

  const btnDelete = document.createElement('button');
  btnDelete.classList.add("btn", "btn-danger", "delete");
  btnDelete.innerHTML = 'Delete';

  EditTd.appendChild(btnEdit);
  DeleteTd.appendChild(btnDelete);

	tr.appendChild(indexTd);
	tr.appendChild(textTd);
	tr.appendChild(EditTd);
	tr.appendChild(DeleteTd);

  tableBody.appendChild(tr);
}



function setOnclick() {

  //delete elem...
  const deleteBtn = document.querySelectorAll('.delete');

  for (let i = 0; i < deleteBtn.length; i++) {
    deleteBtn[i].onclick = function (e) {
      fetch("/item", {
        method: "DELETE",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id: data[e.target.parentNode.parentNode.childNodes[0].innerText-1].id
        })
      })
      .then(res => {
        if(res.ok) reRenderTable()
      })
      .catch(error => console.error(error.message));
    }
  }

  // edit elem...
  const editBtn = document.querySelectorAll('.edit');

  for (let i = 0; i < editBtn.length; i++) {
    editBtn[i].onclick = function (e) {

      const el = data[e.target.parentNode.parentNode.childNodes[0].innerText-1];
      const input = document.querySelector('.modal-body input[type=text]');
      const checkbox = document.querySelector('.modal-body input[type=checkbox]');

      input.value = el.text;
      checkbox.checked = el.ready;

      document.querySelector('.modal-footer .send').onclick = function () {
        fetch("/item", {
          method: "PUT",
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            id: el.id,
            text: input.value,
            ready: checkbox.checked
          })
        })
        .then(res => {
          if(res.ok) reRenderTable()
        })
        .catch(error => console.error(error.message));
      }
    }
  }
}

function reRenderTable() {
  tableBody.innerHTML = '';
  getAllData();
}

getAllData();



})();