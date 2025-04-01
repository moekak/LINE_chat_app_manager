import { createImageBlock, createTextBlock } from "../elementTemplate.js";

// 1. TemplateBlockManager.js - ブロック管理とUI操作に特化
class TemplateBlockManager {
    constructor() {
        this.blockCounter = 1; // すでに2つのブロックがあるため3から開始
    }


    resetBlockCounter(){
        this.blockCounter = 1
    }
    addTextBlock(contentBlocksContainer) {
        const blockId = `block-${this.blockCounter++}`;
        const textBlock = document.createElement('div');
        textBlock.className = 'content-block text-block';
        textBlock.draggable = true;
        textBlock.dataset.type = 'text';
        textBlock.dataset.id = blockId;
        textBlock.innerHTML = createTextBlock();
    
        contentBlocksContainer.appendChild(textBlock);
        return textBlock; // 新しく作成したブロックを返す
    }
    
    addImageBlock(contentBlocksContainer) {
        const blockId = `block-${this.blockCounter++}`;
        const imageBlock = document.createElement('div');
        imageBlock.className = 'content-block image-block';
        imageBlock.draggable = true;
        imageBlock.dataset.type = 'image';
        imageBlock.dataset.id = blockId;
        imageBlock.innerHTML = createImageBlock(this.blockCounter);

        contentBlocksContainer.appendChild(imageBlock);
        return imageBlock; // 新しく作成したブロックを返す
    }

    setupBlockListeners(block) {
        // 削除ボタンのイベントリスナー
        const deleteBtn = block.querySelector('.delete-block');
        deleteBtn.addEventListener('click', () => {
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