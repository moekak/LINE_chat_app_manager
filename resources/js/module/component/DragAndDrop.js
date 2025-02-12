import formDataStateManager from "../util/state/FormDataStateManager.js";

class DragAndDrop{
    
    static dragAndDrop(id, changeOrder = false){
        const elem = document.getElementById(id);
        const options = {
            animation: 150,
            handle: '.drag-handle',
            onEnd(evt) {  // onEndを直接ここに定義
                if (changeOrder) {  // 条件チェックをonEnd内部で行う

                    const items = document.querySelectorAll(".js_data");
                    const headings = document.querySelectorAll(".js_headings")
                    const formDataArray = formDataStateManager.getState()

                    const newDataArray = []
                    Array.from(items).forEach((item, index) => {
                        newDataArray[index] = formDataArray[item.getAttribute("data-id")]
                        item.setAttribute("data-id", index)
                    });
                    Array.from(headings).forEach((heading, index) => {
                        heading.setAttribute('data-id', index);
                    });

                    
                    // 一旦formDataをリセットして並び順をかえたものをセットする
                    formDataStateManager.resetItem()
                    formDataStateManager.addData(newDataArray)
                    
                    
                }
            }
        };
    
    
        Sortable.create(elem, options);
    }
}

export default DragAndDrop;