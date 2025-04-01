class InitializeInputService{
    static intiaizeInputs(){
        const templateNames = document.querySelectorAll(".template-title")
        const categoryId = document.getElementById("category-select")
        const blockContents = document.getElementById("content-blocks")
        const errorelement = document.getElementById("form-errors")
        const errorList = document.getElementById("js_error_list")

        errorelement.classList.add("hidden")
        errorList.innerHTML = ""
        templateNames.forEach((templateName)=>{
            templateName.value = "" 
        })

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

    static initializeErrorList(){
        const errorList = document.getElementById("js_error_list")
        const errorWrapper = document.getElementById("form-errors")

        errorList.innerHTML = ""
        errorWrapper.classList.add("hidden")
    }
}

export default InitializeInputService