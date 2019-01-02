window.fillDonut = (selector, data) => {
    const svgns = 'http://www.w3.org/2000/svg';

    const { amount, color, desc } = data[0];

    const total = data.reduce((current, piece) => current + piece.amount, 0);

    let currentSegmentOffset = 25;

    // var gapSize = 0.5;

    if (data.length === 0) {
        return;
    }

    for (let index = 0; index < data.length; index += 1) {
        let strokeWidth = 1;
        const segment = data[index];
        const segmentWidth = (segment.amount / total) * 100;
        const segmentRemainder = 100 - segmentWidth;
        const segmentOffset = currentSegmentOffset;
        const segmentColor = segment.color;
        if (index === 0) {
            strokeWidth = 2;
        }

        // segmentRemainder += gapSize;
        // segmentOffset -= gapSize/2;

        // Add / render segment in SVG (& account for segment gaps)

        const circle = document.createElementNS(svgns, 'circle');
        circle.setAttributeNS(null, 'class', 'donut-segment');
        circle.setAttributeNS(null, 'id', `segment${index}`);
        circle.setAttributeNS(null, 'cx', '21');
        circle.setAttributeNS(null, 'cy', '21');
        circle.setAttributeNS(null, 'r', '15.91549430918954');

        // circle.setAttributeNS(null, 'style', 'fill: transparent; stroke: '+segmentColor+'; stroke-width: 2; stroke-dasharray: '+(segmentWidth - gapSize) +' '+segmentRemainder+'; stroke-dashoffset: '+segmentOffset+';');
        circle.setAttributeNS(null, 'style', `fill: transparent; stroke: ${segmentColor}; stroke-width: ${strokeWidth}; stroke-dasharray: ${segmentWidth} ${segmentRemainder}; stroke-dashoffset: ${segmentOffset};`);


        $(selector).append(circle);
        $(`${selector} .chart-number`).html(amount).attr('fill', color);
        $(`${selector} .chart-text`).html(desc).attr('fill', color);

        currentSegmentOffset -= segmentWidth;
    }
};
