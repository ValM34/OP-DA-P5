// check all checkbox or uncheck all
export function functionTest(){
    let selectAllCheckbox = document.querySelectorAll('input[name="checkboxPost"]');
    let globalCheckbox = document.querySelector('#checkbox-all');
    console.log(selectAllCheckbox)

    globalCheckbox.addEventListener('change', function () {
        if (this.checked) {
            for (let i = 0; i < selectAllCheckbox.length; i++) {
                selectAllCheckbox[i].checked = true;
            }
        } else {
            for (let i = 0; i < selectAllCheckbox.length; i++) {
                selectAllCheckbox[i].checked = false;
            }
        }
    });
}