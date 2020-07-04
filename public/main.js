document.addEventListener('DOMContentLoaded', init);

function init() {
	document.getElementById('emailForm').addEventListener('submit', sendEmail);
}

function sendEmail(event) {
	event.preventDefault();

	var formdata = new FormData();
	formdata.append('recipient', document.getElementById('recipient').value);
	formdata.append('email', document.getElementById('email').value);
	let fileInput = document.getElementById('file1');
	if (fileInput.files[0]) {
		formdata.append('file1', fileInput.files[0], fileInput.files[0].name);
	}
	formdata.append('subject', document.getElementById('subject').value);
	formdata.append('body', document.getElementById('body').value);
	formdata.append('alt-body', document.getElementById('alt-body').value);

	var requestOptions = {
		method: 'POST',
		body: formdata,
		redirect: 'follow',
	};

	fetch('http://localhost/mail.php', requestOptions)
		.then((response) => {
			if (response.status == 200) {
				swal('Done!', 'You email is successfully send!!', 'success');
				clearForm();
			}
		})
		.catch((error) => {
			swal('Oops', 'Something went wrong!', 'error');
		});
}

function clearForm() {
	document.getElementById('emailForm').reset();
}
