import { createImageBlock, createTextBlock } from "../elementTemplate.js";

// 1. TemplateBlockManager.js - ブロック管理とUI操作に特化
class TemplateBlockManager {
    constructor() {
        // シングルトンインスタンスが既に存在する場合はそれを返す
        if (TemplateBlockManager.instance) {
            return TemplateBlockManager.instance;
        }
        
        // 新しいインスタンスを作成
        TemplateBlockManager.instance = this;
        
        this.blockCounter = document.querySelectorAll(".image-block").length; 
    }
    
    // シングルトンインスタンスを取得するための静的メソッド
    static getInstance() {
        if (!TemplateBlockManager.instance) {
            TemplateBlockManager.instance = new TemplateBlockManager();
        }
        return TemplateBlockManager.instance;
    }


    resetBlockCounter(){
        this.blockCounter = 1
    }


    addTextBlock(contentBlocksContainer) {
        const blockId = `block-${this.blockCounter++}`;
        const textBlock = document.createElement('div');
        textBlock.className = 'content-block text-block';
        textBlock.dataset.type = 'text';
        textBlock.dataset.id = blockId;
        textBlock.innerHTML = createTextBlock();
    
        contentBlocksContainer.appendChild(textBlock);
        return textBlock; // 新しく作成したブロックを返す
    }
    
    addImageBlock(contentBlocksContainer, hasData = false) {

        const blockId = `block-${this.blockCounter++}`;
        const imageBlock = document.createElement('div');
        imageBlock.className = 'content-block image-block';

        imageBlock.dataset.type = 'image';
        imageBlock.dataset.id = blockId;
        imageBlock.innerHTML = createImageBlock(this.blockCounter);

        contentBlocksContainer.appendChild(imageBlock);

        const fileId = imageBlock.querySelector(".file-input").id
        document.querySelector(".image_edit_modal").querySelector(".change_img").htmlFor = fileId
        if(hasData){
            console.log("hasDataaaa");
            
            document.querySelector(".change_img").id = "fileInputEdit"
        }
        

        return imageBlock; // 新しく作成したブロックを返す
    }

    setupBlockListeners(block) {
        // 削除ボタンのイベントリスナー
        const deleteBtn = block.querySelector('.delete-block');
        deleteBtn.addEventListener('click', (e) => {
            
            e.preventDefault();
            block.remove();
        });
        // 他のブロック関連リスナーもここに追加
    }

    // すべての現在のブロックを取得
    getAllBlocks() {
        return document.querySelectorAll('.content-block');
    }
}

export default TemplateBlockManager;