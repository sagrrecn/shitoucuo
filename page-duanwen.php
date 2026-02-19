<?php
/**
 * 短文
 * @package custom
 */
if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
} ?>

<!DOCTYPE html>
<html lang="zh">
<?php $this->need("header.php"); ?>
<body class="jasmine-body" style="margin-top:5rem;">
<div class="jasmine-container grid grid-cols-12">
        <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
            <?php $this->need("component/menu.php"); ?>
            <div class="flex flex-col gap-y-12">
                <div class="markdown-body dark:!bg-[#161829] dark:!bg-[#0d1117] !text-neutral-900 dark:!text-gray-400" itemprop="articleBody">
                    <?php echo handleContent($this->content);?>
                </div>
                
                <div id="memos-container">
  <div id="memos-list"></div>
  <div id="memos-pagination"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const bbUrl = "https://me.xgsd.cc/api/v1/memo?creatorId=1&rowStatus=NORMAL&limit=30";
  let currentPage = 1;
  const itemsPerPage = 10;
  let allMemos = [];

  // 获取数据
  fetch(bbUrl)
    .then(res => res.json())
    .then(resdata => {
      allMemos = resdata;
      renderMemos();
      renderPagination();
    })
    .catch(error => {
      console.error('获取数据失败:', error);
      document.getElementById('memos-list').innerHTML = '<div style="text-align: center; color: #666; padding: 20px;">暂时无法加载动态</div>';
    });

  // 渲染备忘录列表
  function renderMemos() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentMemos = allMemos.slice(startIndex, endIndex);
    
    let html = '';
    
    currentMemos.forEach(memo => {
      const bbTime = new Date(memo.createdTs * 1000).toLocaleString();
      let bbCont = memo.content;
      
      // 处理图片
      let imagesHTML = '';
      if (memo.resourceList && memo.resourceList.length > 0) {
        const images = memo.resourceList;
        const imageCount = images.length >= 4 ? 4 : images.length >= 2 ? 2 : 0;
        
        if (imageCount > 0) {
          imagesHTML = '<div class="memo-images">';
          for (let j = 0; j < imageCount; j++) {
            const imgUrl = images[j].externalLink;
            imagesHTML += `<img src="${imgUrl}" alt="Memo图片" onerror="this.style.display='none'">`;
          }
          imagesHTML += '</div>';
        }
      }
      
      // 处理内容中的链接
      const newbbCont = bbCont
        .replace(/!\[.*?\]\((.*?)\)/g, ' <a href="$1" target="_blank">🌅</a> ')
        .replace(/\[(.*?)\]\((.*?)\)/g, ' <a href="$2" target="_blank">$1 🔗</a> ');
      
      html += `
        <div class="memo-item">
          <div class="memo-header">
            <span class="memo-time">${bbTime}</span>
          </div>
          <div class="memo-content">${newbbCont}</div>
          ${imagesHTML}
        </div>
      `;
    });
    
    document.getElementById('memos-list').innerHTML = html;
  }

  // 渲染分页
  function renderPagination() {
    const totalPages = Math.ceil(allMemos.length / itemsPerPage);
    let paginationHTML = '';
    
    if (totalPages > 1) {
      paginationHTML = '<div class="pagination">';
      
      // 上一页
      if (currentPage > 1) {
        paginationHTML += `<button class="page-btn" onclick="changePage(${currentPage - 1})">上一页</button>`;
      }
      
      // 页码
      for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
          paginationHTML += `<span class="page-current">${i}</span>`;
        } else {
          paginationHTML += `<button class="page-btn" onclick="changePage(${i})">${i}</button>`;
        }
      }
      
      // 下一页
      if (currentPage < totalPages) {
        paginationHTML += `<button class="page-btn" onclick="changePage(${currentPage + 1})">下一页</button>`;
      }
      
      paginationHTML += '</div>';
    }
    
    document.getElementById('memos-pagination').innerHTML = paginationHTML;
  }

  // 切换页面
  window.changePage = function(page) {
    currentPage = page;
    renderMemos();
    renderPagination();
    window.scrollTo(0, 0);
  };
});
</script>

<style>
.dark .memo-item{
    background: rgb(10 12 25 / 1);
}
.dark .memo-item:hover{border:1px solid #f15a22;}
.dark .memo-content {color: rgb(156 163 175 / 1) !important;}

#memos-container {
  max-width: 800px;
  margin: 0 auto;
}

.memo-item {
  background: #fff;
  border-radius: 10px;
  padding: 16px;
  margin-bottom: 30px;
  border: 1px solid rgba(0, 0, 0, 0);
}

.memo-header {
  margin-bottom: 8px;
}

.memo-time {
  color: #657786;
  font-size: 16px;
}

.memo-content {
  color: #14171a;
  font-size: 16px;
  line-height: 1.5;
  margin-bottom: 8px;
}

.memo-content a {
  color: #1b95e0;
  text-decoration: none;
}

.memo-content a:hover {
  text-decoration: underline;
}

.memo-images {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 8px;
}

.memo-images img {
  width: calc(25% - 6px);
  aspect-ratio: 1/1;
  object-fit: cover;
  border-radius: 10px;
}

/* 1张图片时居中显示 */
.memo-images img:only-child {
  width: 200px;
  margin: 0 auto;
}

/* 2张图片时各占50% */
.memo-images img:nth-child(1):nth-last-child(2),
.memo-images img:nth-child(2):nth-last-child(1) {
  width: calc(50% - 4px);
}

/* 3张图片时特殊处理 */
.memo-images img:nth-child(1):nth-last-child(3),
.memo-images img:nth-child(2):nth-last-child(2),
.memo-images img:nth-child(3):nth-last-child(1) {
  width: calc(33.333% - 6px);
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.page-btn {
  padding: 4px 11px;
  border: 1px solid #f15a22;
  background: #fff;
  border-radius: 50px;
  cursor: pointer;
  font-size: 14px;
}
.dark .page-btn{background: rgb(10 12 25 / 1);}

.page-current {
  padding: 6px 13px;
  background: #f15a22;
  color: #fff;
  border-radius: 50px;
  font-size: 14px;
}

/* 响应式设计 */
@media (max-width: 768px) {
  #memos-container {
    padding: 10px;
  }
  
  .memo-item {
    padding: 12px;
  }
  
  .memo-images img {
    width: calc(50% - 4px);
  }
  
  /* 移动端3张图片时改为2+1布局 */
  .memo-images img:nth-child(1):nth-last-child(3),
  .memo-images img:nth-child(2):nth-last-child(2),
  .memo-images img:nth-child(3):nth-last-child(1) {
    width: calc(50% - 4px);
  }
  
  .memo-images img:nth-child(3):nth-last-child(1) {
    width: 100%;
  }
}
</style>
                <!--<div class="border-b-2 border-stone-100 dark:border-neutral-600"></div>-->
            </div>
        </div>
      
    </div>
    <?php $this->need("footer.php"); ?>
</body>
</html>
