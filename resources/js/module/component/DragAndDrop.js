class DragAndDrop {
    static dragAndDrop(id, formDataStateManager, changeOrder = false) {
        const elem = document.getElementById(id);
        
        // 深いコピーで初期状態を保存
        const initialState = JSON.parse(JSON.stringify(formDataStateManager.getState()));

        console.log("Initial state at start of dragAndDrop:", initialState);

        const options = {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function(evt) {
                if (changeOrder) {
                    console.log("Initial state at onEnd:", initialState);
                    console.log("Current state in formDataStateManager:", formDataStateManager.getState());

                    const items = document.querySelectorAll(".js_data");
                    const headings = document.querySelectorAll(".js_headings");

                    const newDataArray = [];
                    Array.from(items).forEach((item, index) => {
                        const dataId = item.getAttribute("data-id");
                        console.log(`Item: ${item}, Data ID: ${dataId}`);
                        newDataArray[index] = initialState[dataId];
                        item.setAttribute("data-id", index);
                    });

                    Array.from(headings).forEach((heading, index) => {
                        heading.setAttribute('data-id', index);
                    });

                    console.log("New Data Array:", newDataArray);

                    // 一旦formDataをリセットして並び替え後のデータを追加
                    formDataStateManager.resetItem();
                    console.log("After reset:", formDataStateManager.getState());

                    formDataStateManager.addData(newDataArray);
                    console.log("After addData:", formDataStateManager.getState());
                }
            }
        };

        Sortable.create(elem, options);
    }
}

export default DragAndDrop;
