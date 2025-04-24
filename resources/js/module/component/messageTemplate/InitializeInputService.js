class InitializeInputService{
    static intiaizeInputs(){
        const templateNames = document.querySelectorAll(".template-title")
        const categoryId = document.getElementById("category-select")
        const categoryOptions = document.querySelectorAll(".category-option")
        const blockContents = document.getElementById("create-content-blocks")
        const errorelement = document.getElementById("form-errors")
        const errorList = document.getElementById("js_error_list")
        const createForm = document.querySelector(".js_create_form")
        const editFrom = document.querySelector(".tab-edit")
        const editShowForm = document.querySelector(".js_edit_form")
        const categoryForm = document.querySelector(".js_category_form")

        errorelement.classList.add("hidden")
        errorList.innerHTML = ""
        templateNames.forEach((templateName)=>{
            templateName.value = "" 
        })

        categoryId.value = ""
        blockContents.innerHTML = ""

        categoryOptions.forEach((option)=>{
            option.selected = false
            if(option.innerHTML === "カテゴリーを選択"){
                option.selected = true
            }
        })


        createForm.style.display ="block"
        editFrom.classList.add("hidden")
        editShowForm.classList.add("hidden")
        categoryForm.classList.add("hidden")

        document.getElementById("js_category_input").value = ""
        document.getElementById("js_tab_new").classList.add("active")
        document.getElementById("js_tab_edit").classList.remove("active")
        document.getElementById("js_tab_category").classList.remove("active")

    }



    static initializeCategoryInput(){
        const categoryName = document.getElementById("js_category_input")
        categoryName.value = ""
    }

    static initializeErrorList(){
        const errorList = document.getElementById("js_error_list")
        const errorWrapper = document.getElementById("form-errors")

        errorList.innerHTML = ""
        errorWrapper.classList.add("hidden")
    }
}

export default InitializeInputService