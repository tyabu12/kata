"use strict";

import * as fs from "fs";
import * as files from "files";
import * as config from "./common/config";

export default (path, opts) => {
  files.assertInRepo();
  config.assertNotBare();
  opts = opts || {};

  const pathFromRoot = files.pathFromRepoRoot(path);
  const isOnDisk = fs.existsSync(path);
  const isInIndex = index.hasFile(path, 0);
};
