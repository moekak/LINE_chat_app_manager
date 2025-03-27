class InitializeInputService{
    static intiaizeInputs(){
        const templateName = document.getElementById("template-title")
        const categoryId = document.getElementById("category-select")
        const blockContents = document.getElementById("content-blocks")
        const errorelement = document.getElementById("form-errors")
        const errorList = document.getElementById("js_error_list")

        errorelement.classList.add("hidden")
        errorList.innerHTML = ""
        templateName.value = ""
        categoryId.value = ""
        categoryId.innerHTML = ""
        blockContents.innerHTML = ""

        const option = document.createElement("option")
        option.disabled = true
        option.selected = true
        option.innerHTML = "カテゴリーを選択"

        categoryId.append(option)
    }

    static initializeCategoryInput(){
        const categoryName = document.getElementById("js_category_input")
        categoryName.value = ""
    }
}

export default InitializeInputService