async function dilateImage(image, width, height, dilate=[3, 3]) {
    const input = await gm.imageTensorFromURL(image, 'uint8', [height, width, 4], true);
    let pipeline = input
    pipeline = gm.dilate(pipeline, dilate);

    // allocate output tensor
    const output = gm.tensorFrom(pipeline);
    const sess = new gm.Session();

    sess.init(pipeline);
    sess.runOp(pipeline, 0, output);

    const canvasProcessed = gm.canvasCreate(width, height);
    gm.canvasFromTensor(canvasProcessed, output);
    return canvasProcessed;
}