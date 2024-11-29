const gulp = require('gulp');
const concat = require('gulp-concat');
// const uglify = require('gulp-uglify');
const rollup = require('rollup');
const babel = require('gulp-babel');
const minify = require('gulp-babel-minify');
const { series } = require('gulp');

const buildDest = 'public/js/room';
const files = {
    room2d: {
        name: 'interior2d',
        source: 'public/js/src/2d/interior2d.js',
        rollup: 'public/js/src/interior2d.js',
        dest: buildDest,
        build: '2d.min.js',
        other: [],
    },
    room2dEdit: {
        name: 'interior2dEdit',
        source: 'public/js/src/2d/interior2dEdit.js',
        rollup: 'public/js/src/interior2dEdit.js',
        dest: buildDest,
        build: '2dEdit.min.js',
        other: [],
    },
    room3d: {
        name: 'interior3d',
        source: 'public/js/src/3d/interior3d.js',
        rollup: 'public/js/src/interior3d.js',
        dest: buildDest,
        build: '3d.min.js',
        other: [
            'public/js/src/3d/DeviceOrientationControls.js',
            'public/js/src/3d/FirstPersonControls.js',
            'public/js/src/3d/Mirror.js',
            'public/js/src/3d/OrbitControls.js',
            'public/js/src/3d/StereoEffect.js',
        ],
    },
    panorama: {
        name: 'interiorPanorama',
        source: 'public/js/src/panorama/interiorPanorama.js',
        rollup: 'public/js/src/interiorPanorama.js',
        dest: buildDest,
        build: 'panorama.min.js',
        other: [],
    },
    roomPlanner: {
        name: 'RoomPlanner',
        source: 'public/js/src/RoomPlanner/RoomPlanner.js',
        rollup: 'public/js/src/RoomPlanner.js',
        dest: buildDest,
        build: 'RoomPlanner.min.js',
        other: [],
    },
    tilesDesigner: {
        name: 'tilesDesigner',
        source: 'public/js/src/TilesDesigner/TilesDesigner.js',
        rollup: 'public/js/src/tilesDesigner.js',
        buildSub: '/TilesDesigner',
        dest: buildDest + '/TilesDesigner',
        build: 'tilesDesigner.min.js',
        other: [],
    },
    blueprint3d: {
        name: 'blueprint3d',
        source: 'public/js/src/blueprint3d/room.js',
        rollup: 'public/js/src/blueprint3d.js',
        dest: 'public/js/blueprint3d',
        build: 'blueprint3d.min.js',
        other: [
            'public/js/src/blueprint3d/items.js',
        ],
    },
    blueprint3dLib: {
        name: 'blueprint3dLib',
        source: 'public/js/src/blueprint3d/blueprint3d.js',
        rollup: 'public/js/src/blueprint3d.lib.js',
        dest: 'public/js/blueprint3d',
        build: 'blueprint3d.lib.min.js',
        other: [],
    },
    three69: {
        name: 'three.r69',
        source: 'public/js/src/blueprint3d/three.js',
        rollup: 'public/js/src/three.r69.min.js',
        dest: 'public/js/blueprint3d',
        build: 'three.min.js',
        format: 'es',
        other: [],
    },
};

async function rollupScript(files) {
    const bundle = await rollup.rollup({
        input: files.source,
    });
    bundle.write({
    // await bundle.write({
        file: files.rollup,
        format: files.format || 'iife', //'amd', 'cjs', 'system', 'es', 'iife' or 'umd'
        name: files.name,
    });
}

const rol2d = async () => await rollupScript(files.room2d);
const rol2dEdit = async () => await rollupScript(files.room2dEdit);
const rol3d = async () => await rollupScript(files.room3d);
const rolPan = async () => await rollupScript(files.panorama);
const rolRP = async () => await rollupScript(files.roomPlanner);
const rolTD = async () => await rollupScript(files.tilesDesigner);
const rolBP = async () => await rollupScript(files.blueprint3d);
const rolBPLib = async () => await rollupScript(files.blueprint3dLib);
const rolThree = async () => await rollupScript(files.three69);

gulp.task('rol2d', rol2d);
gulp.task('rol2dEdit', rol2dEdit);
gulp.task('rol3d', rol3d);
gulp.task('rolPan', rolPan);
gulp.task('rolRP', rolRP);
gulp.task('rolTD', rolTD);
gulp.task('rolBP', rolBP);
gulp.task('rolBPLib', rolBPLib);
gulp.task('rolThree', rolThree);

function babelScript(files) {
    return gulp.src(files.other.concat(files.rollup))
        .pipe(babel())
        .pipe(minify())
        .pipe(concat(files.build))
        .pipe(gulp.dest(files.dest));
}

// gulp.task('babTD', ['rolTD'], () => babelScript(files.tilesDesigner));

function minifyScript(files) {
    return gulp.src(files.other.concat(files.rollup))
        .pipe(babel())
        // .pipe(minify())
        .pipe(concat(files.build))
        .pipe(gulp.dest(files.dest));
}

const min2d = () => minifyScript(files.room2d);
const min2dEdit = () => minifyScript(files.room2dEdit);
const min3d = () => minifyScript(files.room3d);
const minPan = () => minifyScript(files.panorama);
const minRP = () => minifyScript(files.roomPlanner);
const minTD = () => minifyScript(files.tilesDesigner);
const minBP = () => minifyScript(files.blueprint3d);
const minBPLib = () => minifyScript(files.blueprint3dLib);
const minThree = () => minifyScript(files.three69);

gulp.task('min2d', min2d);
gulp.task('min2dEdit', min2dEdit);
gulp.task('min3d', min3d);
gulp.task('minPan', minPan);
gulp.task('minRP', minRP);
gulp.task('minTD', minTD);
gulp.task('minBP', minBP);
gulp.task('minBPLib', minBPLib);
gulp.task('minThree', minThree);

gulp.task('2d', series(rol2d, min2d));
gulp.task('2dEdit', series(rol2dEdit, min2dEdit));
gulp.task('3d', series(rol3d, min3d));
gulp.task('pan', series(rolPan, minPan));
gulp.task('RP', series(rolRP, minRP));
gulp.task('TD', series(rolTD, minTD));
gulp.task('BP', series(rolBP, minBP));
gulp.task('BPLib', series(rolBPLib, minBPLib));
gulp.task('Three', series(rolThree, minThree));

// gulp.task('rol', ['rol2d', 'rol2dEdit', 'rol3d', 'rolPan', 'rolRP', 'rolTD']);
// gulp.task('min', ['min2d', 'min2dEdit', 'min3d', 'minPan', 'minRP', 'minTD']);

// gulp.task('build', ['2d', '2dEdit', '3d', 'pan', 'RP', 'babTD']);

// // The default task (called when you run `gulp` from cli)
// gulp.task('default', ['rol']);
