import * as files from "./common/files";
import * as config from "./common/config";

export default opts => {
  if (files.inRepo()) return;

  opts = opts || {};

  const gitletStructure = {
    HEAD: "ref: refs/heads/master\n",

    config: config.objToStr({ core: { "": { bare: opts.bare === true } } }),

    objects: {},
    refs: {
      heads: {}
    }
  };

  const tree = opts.bare ? gitletStructure : { ".gitlet": gitletStructure };

  files.writeFilesFromTree(tree, process.cwd());

  return tree;
};
