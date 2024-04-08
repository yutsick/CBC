// Tooltips

document.addEventListener('DOMContentLoaded', function() {
    
    const donTypeTool = document.querySelector('#field_x5czf2_label');
    const donAnonTool = document.querySelector('#donAnontooltip');
    const referTool = document.querySelector('#field_dx2vs3_label');
    
    const donTypeBlock = document.querySelector('.tooltip-block.don-type');
    const anonBlock = document.querySelector('.tooltip-block.don-anonymous');
    const refBlock = document.querySelector('.tooltip-block.ref');   
    
    if (donTypeTool) {
        donTypeTool.addEventListener('mouseover', () => {
            donTypeBlock.style.display = 'flex';
        });

        donTypeTool.addEventListener('mouseleave', () => {
            donTypeBlock.style.display = 'none';
        });
    };

    if (donAnonTool) {
        donAnonTool.addEventListener('mouseover', () => {
            anonBlock.style.display = 'flex';
        });

        donAnonTool.addEventListener('mouseleave', () => {
            anonBlock.style.display = 'none';
        });
    };

    if (referTool) {
        referTool.addEventListener('mouseover', () => {
            refBlock.style.display = 'flex';
        });

        referTool.addEventListener('mouseleave', () => {
            refBlock.style.display = 'none';
        });
    };
});