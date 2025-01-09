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
    
                    Array.from(items).forEach((item, index) => {
                        item.setAttribute('data-id', index);
                    });
                    Array.from(headings).forEach((heading, index) => {
                        heading.setAttribute('data-id', index);
                    });
                }
            }
        };
    
    
        Sortable.create(elem, options);
    }
}

export default DragAndDrop;