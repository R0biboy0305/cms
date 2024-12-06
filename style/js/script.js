function updateFormFields() {
    const type = document.getElementById('choix').value;
    
    const textField = document.getElementById('textField');
    const fileField = document.getElementById('fileField');
    const altField = document.getElementById('altField');
    const columnFiled = document.getElementById('columnFiled')

    if (type === 'img') {
    
        textField.style.display = 'none';
        fileField.style.display = 'block';
        altField.style.display = 'block';

    } else if (type == 'figure' ) {

        textField.style.display = 'block';
        fileField.style.display = 'block';
        altField.style.display = 'block';
        columnFiled.style.display = 'block';

    } else {

        textField.style.display = 'block';
        fileField.style.display = 'none';
        altField.style.display = 'none';

    }

};


document.addEventListener("DOMContentLoaded", () => {
    const deleteLinks = document.querySelectorAll(".delete-link");

    deleteLinks.forEach(link => {
        link.addEventListener("click", event => {
            if (!confirm("La suppresion de cet article entrainera celle de ses blocs")) {
                event.preventDefault(); 
            }
        });
    });
});
