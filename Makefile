GEN_DIR := $(shell git rev-parse --show-toplevel)
PROTOC_PHP_PLUGIN_PATH := $(shell which grpc_php_plugin)
PROTO_SRC_DIR := $(GEN_DIR)/milvus-proto/proto
PROTO_DEST_DIR := $(GEN_DIR)/Milvus


protos-generate:
	$(foreach file, \
		$(wildcard $(PROTO_SRC_DIR)/*), \
		$(shell protoc \
			--proto_path=$(PROTO_SRC_DIR) \
			--php_out=$(GEN_DIR) \
			--grpc_out=$(GEN_DIR) \
			--plugin=protoc-gen-grpc=$(PROTOC_PHP_PLUGIN_PATH) \
			$(file)))



docker-protos-generate:
	$(eval proto_dest_dir=$(subst /Milvus,,$(subst $(GEN_DIR),/project,$(PROTO_DEST_DIR))))
	$(eval proto_src_dir=$(subst $(GEN_DIR),/project,$(PROTO_SRC_DIR)))

	$(foreach file, \
		$(wildcard $(PROTO_SRC_DIR)/*), \
		$(shell docker run -v "/home/avolosyuk/Projects/php/milvus_php:/project" \
			php-grpc:7.0.33 protoc \
			--proto_path=${proto_src_dir} \
			--php_out=${proto_dest_dir} \
			--grpc_out=${proto_dest_dir} \
			--plugin=protoc-gen-grpc=/usr/local/bin/grpc_php_plugin \
			$(subst $(GEN_DIR),/project,$(file))))


protos-cleanup:
	rm -rf $(PROTO_DEST_DIR)



# docker run php-grpc:7.0.33 -v "/home/avolosyuk/Projects/php/milvus_php:/project protoc" --php_out=/project/Milvus  --php-grpc_out=/project/Milvus  --proto_path=/project/milvus-proto/proto --plugin=protoc-gen-php-grpc=/usr/local/bin/grpc_php_plugin /project/milvus-proto/proto/common.proto