import { createImageBlock, createTextBlock } from "../elementTemplate.js";

class MessageTemplateOperator{
    constructor(){
        this.contentBlocks = document.getElementById('content-blocks');
        this.addTextBtn = document.getElementById('add-text');
        this.addImageBtn = document.getElementById('add-image');
        this.previewContainer = document.getElementById('preview-container');
        // カウンター（ユニークなIDを生成するため）
        this.blockCounter = 3 // すでに2つのブロックがあるため3から開始
        this.tabs = document.querySelectorAll('.tab');
        this.tabContents = document.querySelectorAll('.tab-content');
        this.initialize()
    }

    initialize(){
        // 新しいブロックが追加されたときのためのMutationObserverを設定
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    this.#setupSortableBlocks();
                }
            });
        });

        observer.observe(this.contentBlocks, { childList: true });

        this.#setupSortableBlocks();
        // タブ切り替え
        this.#toggleTabs()
        // ボタンのクリックイベントをセットアップ
        this.addTextBtn.addEventListener('click', this.#addTextBlock.bind(this));
        this.addImageBtn.addEventListener('click', this.#addImageBlock.bind(this));

        // 初期ブロックのリスナーをセットアップ
        document.querySelectorAll('.content-block').forEach(block => {
            this.#setupBlockListeners(block);
        });

        this.contentBlocks.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
    }

    // テキストブロックを追加
    #addTextBlock(){
        const blockId = `block-${this.blockCounter++}`;
        const textBlock = document.createElement('div');
        textBlock.className = 'content-block text-block';
        textBlock.draggable = true;
        textBlock.dataset.type = 'text';
        textBlock.dataset.id = blockId;

        textBlock.innerHTML = createTextBlock();
    
        console.log(this.contentBlocks);
        
        this.contentBlocks.appendChild(textBlock);
        this.#setupBlockListeners(textBlock);
    }
    
    // 画像ブロックを追加
    #addImageBlock(){
        const blockId = `block-${this.blockCounter++}`;
        const imageBlock = document.createElement('div');
        imageBlock.className = 'content-block image-block';
        imageBlock.draggable = true;
        imageBlock.dataset.type = 'image';
        imageBlock.dataset.id = blockId;

        imageBlock.innerHTML = createImageBlock()

        this.contentBlocks.appendChild(imageBlock);
        this.#setupBlockListeners(imageBlock);
    }


    #setupBlockListeners(block){
        // 削除ボタンのイベントリスナー
        const deleteBtn = block.querySelector('.delete-block');
        deleteBtn.addEventListener('click', () => {
            block.remove();
        });
        
        // ドラッグ＆ドロップのイベントリスナー
        block.addEventListener('#dragStart', this.#dragStart.bind(this))
        block.addEventListener('#dragEnd', this.#dragEnd.bind(this));
    }

    #dragStart(e){
        console.log(e.currentTarget);
        
        e.currentTarget.classList.add('dragging');
        e.dataTransfer.setData('text/plain', this.dataset.id);
    }

    #dragEnd(e){
        e.currentTarget.classList.remove('dragging');
    }

    #dargOver(e){
        e.preventDefault();
        const draggingBlock = document.querySelector('.dragging');
        const targetBlock = this;
        
        if (draggingBlock !== targetBlock) {
            const container = document.getElementById('content-blocks');
            const blockRect = targetBlock.getBoundingClientRect();
            const mouseY = e.clientY;
            
            // マウスがブロックの上半分にあるかどうかを判定
            const isAboveHalf = mouseY < blockRect.top + blockRect.height / 2;
            
            console.log(draggingBlock);
            
            if (isAboveHalf) {
                container.insertBefore(draggingBlock, targetBlock);
            } else {
                container.insertBefore(draggingBlock, targetBlock.nextSibling);
            }
        }
    }

    // ブロック間のドラッグ＆ドロップを可能にする
    #setupSortableBlocks(){
        const blocks = document.querySelectorAll('.content-block');
            
        blocks.forEach(block => {
            block.addEventListener('dragover', this.#dargOver);
        });
    }

    #toggleTabs(){
        this.tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                // アクティブタブの更新
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // タブコンテンツの表示/非表示
                this.tabContents.forEach(content => {
                    content.style.display = 'none';
                });
                this.tabContents[index].style.display = 'block';
            });
        });
    }
}

export default MessageTemplateOperator;